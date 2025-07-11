import { Component, OnInit } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';
import { ModalComponent } from '../modal/modal.component';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { RideService } from '../ride.service';
import { Ride } from '../ride.model';
import { FormsModule } from '@angular/forms';
import { SearchParams } from '../../search-params/search-params.model';
import { Router } from '@angular/router';

function computeArrivalTime(start: string, duration: string): string {
  const [h, m] = start.split('h').map(Number);
  const [dh, dm] = duration.split('h').join(':').split(':').map(Number);
  const date = new Date();
  date.setHours(h + dh, m + dm);
  return date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}


@Component({
  selector: 'app-recherche-covoit',
  standalone: true,
  imports: [SearchBarComponent, ModalComponent, CommonModule, FormsModule],
  templateUrl: './recherche-covoit.component.html',
  styleUrl: './recherche-covoit.component.scss'
})
export class RechercheCovoitComponent implements OnInit{
  // UI
  showTripDetail = false;
  step = 1;
  showFilters = false;
  isAlternativeResults = false;
  displayedDate: string = '';
  searchVilleDepart = '';
  searchVilleArrivee = '';
  date = ''; 

  // Passagers
  passengerOptions = Array.from({ length: 10 }, (_, i) => i + 1); 
  selectedPassengers = 1;
  showPassengerDropdown = false;

  // Rides
  rideId!: number;
  rides: Ride[] = [];


  alternativeResults: Ride[] = [];
  
  selectedRide: Ride | null = null;
  

  // Crédits
  credits: number = 0;

  constructor(
    private http: HttpClient,
    private rideService: RideService,
    private router: Router
  ) {}


  ngOnInit(): void {
    const state = history.state as SearchParams;
  
    if (state && state.villeDepart && state.villeArrivee && state.date) {
      this.searchVilleDepart = state.villeDepart;
      this.searchVilleArrivee = state.villeArrivee;
      this.selectedPassengers = state.nbPassagers;
  
      let parsedDate: Date;
  
      // Sécurise la conversion en format yyyy-MM-dd
      if (typeof state.date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(state.date)) {
        this.date = state.date; 
        parsedDate = new Date(state.date);
      } else {
        parsedDate = new Date(state.date);
        this.date = parsedDate.toISOString().split('T')[0]; 
      }
  
      // Affichage utilisateur
      this.displayedDate = this.formatDate(parsedDate.toString());
  
      this.onSearch(state);
    } 
  }
  


  capitalizeFirstLetter(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }


  // Recherche
  onSearch(params: SearchParams) {
    this.searchVilleDepart = params.villeDepart;
    this.searchVilleArrivee = params.villeArrivee;
    this.displayedDate = this.formatDate(params.date);

    const token = localStorage.getItem('token');
    if (!token) {
      alert('Veuillez vous reconnecter.');
      return;
    }
    this.isAlternativeResults = false;
    
  
    this.rideService.searchRides(params).subscribe({
      next: (rides) => {
        console.log('🎯 Rides depuis BDD :', rides);

        if (rides.length > 0) {
          this.rides = rides;
          this.alternativeResults = []; 
        } else {
          this.rides = [];              
          this.loadNextAvailableRides(params);
        }
      },
      error: () => alert('Erreur lors de la recherche')
    });
  }


  loadNextAvailableRides(params: SearchParams) {
    this.rideService.searchNextAvailableRides(params).subscribe({
      next: (nextRides) => {
        console.log('📆 Résultats alternatifs :', nextRides);
        this.isAlternativeResults = true;
        this.alternativeResults = nextRides;

  
        if (nextRides.length > 0 && nextRides[0].date) {
          const firstDate = nextRides[0].date;
          this.displayedDate = this.formatDate(firstDate);
          this.searchVilleDepart = nextRides[0].departureCity;
          this.searchVilleArrivee = nextRides[0].arrivalCity;
        }
      },
      error: () => alert('Aucun trajet disponible.')
    });
  }

  

  formatDate(dateStr: string): string {
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
      weekday: 'long',
      day: 'numeric',
      month: 'long'
    });
  }
  

  // Réservation
  confirmerReservation() {
    const token = localStorage.getItem('token');
    this.http.post(`/api/rides/${this.rideId}/join`, {}, {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe({
      next: () => {
        this.step = 3;
        this.loadCredits();
      },
      error: (err) => {
        alert(err.error?.error || 'Erreur lors de la réservation.');
      }
    });
  }

  loadCredits() {
    const token = localStorage.getItem('token');
    if (!token) return;
  
    this.http.get<any>('/api/rides/list', {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe({
      next: (res) => {
        this.credits = res.credits;
      },
      error: (err) => {
        console.error('Erreur lors du chargement des crédits', err);
      }
    });
  }

  // Modale
  openTripDetail(ride: any) {
    this.selectedRide = ride;
    this.rideId = ride.id;
    this.step = 1;
    this.showTripDetail = true;
  }

  closeTripDetail() {
    this.step = 1;
    this.showTripDetail = false;
  }

  goToStep(n: number) {
    this.step = n;
  
    if (n === 2) {
      this.loadCredits(); 
    }
  }

  // Filtres
  toggleFilters() {
    this.showFilters = !this.showFilters;
  }

  // Passagers
  togglePassengerDropdown() {
    this.showPassengerDropdown = !this.showPassengerDropdown;
  }

  selectPassengers(count: number) {
    this.selectedPassengers = count;
    this.showPassengerDropdown = false;
  }


  getTotalPrice(): number {
    return this.selectedRide?.price ? this.selectedRide.price * this.selectedPassengers : 0;
  }


}


