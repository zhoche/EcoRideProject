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
  passengerOptions = Array.from({ length: 10 }, (_, i) => i + 1); // 👈 ici directement
  selectedPassengers = 1;
  showPassengerDropdown = false;

  // Rides
  rideId!: number;
  rides: Ride[] = [
    {
      id: 1,
      departureTime: '10h20',
      date: '2025-07-15T14:00:00',
      arrivalTime: '11h20',
      departureCity: 'Auch',
      arrivalCity: 'Toulouse',
      duration: '1h00',
      price: 5,
      availableSeats: 2,
      driverName: 'Anthony',
      driverImage: 'images/Profil_Anthony.png',
      rating: 4.7,
      verified: true,
      extras: 'Animaux de compagnie autorisées, non-fumeur'
    },
    {
      id: 2,
      date: '2025-07-19T14:00:00',
      departureTime: '14h00',
      arrivalTime: '15h00',
      departureCity: 'Auch',
      arrivalCity: 'Toulouse',
      duration: '1h00',
      price: 7,
      availableSeats: 0,
      driverName: 'Gauthier',
      driverImage: 'images/Profil_Gauthier.png',
      rating: 4.9,
      verified: true,
      extras: 'Max. 2 à l’arrière'
    }
  ];
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
        this.date = state.date; // Format déjà bon
        parsedDate = new Date(state.date);
      } else {
        parsedDate = new Date(state.date);
        this.date = parsedDate.toISOString().split('T')[0]; // ISO pour le champ input
      }
  
      // Affichage utilisateur
      this.displayedDate = this.formatDate(parsedDate.toString());
  
      this.onSearch(state);
    } else {
      console.warn("❌ Aucun paramètre de recherche trouvé dans le state.");
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
        } else {
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
        this.rides = nextRides;
        this.isAlternativeResults = true;
  
        if (nextRides.length > 0 && nextRides[0].date) {
          const firstDate = nextRides[0].date;
          this.displayedDate = this.formatDate(firstDate);
          this.searchVilleDepart = nextRides[0].departureCity;
          this.searchVilleArrivee = nextRides[0].arrivalCity;        }
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
    this.http.get<any>('/api/me', {
      headers: { Authorization: `Bearer ${token}` }
    }).subscribe({
      next: (user) => this.credits = user.credits
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

}


