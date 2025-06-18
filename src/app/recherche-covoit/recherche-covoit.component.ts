import { Component } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';
import { ModalComponent } from '../modal/modal.component';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-recherche-covoit',
  standalone: true,
  imports: [SearchBarComponent, ModalComponent, CommonModule],
  templateUrl: './recherche-covoit.component.html',
  styleUrl: './recherche-covoit.component.scss'
})
export class RechercheCovoitComponent {
  // --- UI ETAPE ---
  showTripDetail = false;
  step = 1;

  openTripDetail() {
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


  //FILTRES
  showFilters = false;

  toggleFilters() {
    this.showFilters = !this.showFilters;
  }


  // --- DROPDOWN PASSAGERS ---
  passengerOptions = [1, 2, 3, 4];
  selectedPassengers = 1;
  showPassengerDropdown = false;

  togglePassengerDropdown() {
    this.showPassengerDropdown = !this.showPassengerDropdown;
  }

  selectPassengers(count: number) {
    this.selectedPassengers = count;
    this.showPassengerDropdown = false;
  }

  // --- RESERVATION ---
  rideId!: number; // à définir dynamiquement
  credits: number = 0;

  constructor(private http: HttpClient) {}

  confirmerReservation() {
    const token = localStorage.getItem('token');
    this.http.post(`/api/rides/${this.rideId}/join`, {}, {
      headers: {
        Authorization: `Bearer ${token}`
      }
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
}
