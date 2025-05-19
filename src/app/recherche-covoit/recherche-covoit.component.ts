import { Component } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';
import { ModalComponent } from '../modal/modal.component';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-recherche-covoit',
  standalone: true,
  imports: [SearchBarComponent, ModalComponent, CommonModule],
  templateUrl: './recherche-covoit.component.html',
  styleUrl: './recherche-covoit.component.scss'
})
export class RechercheCovoitComponent {
  //FILTRES
  showFilters = false;

  toggleFilters() {
    this.showFilters = !this.showFilters;
  }


 


   // DROPDOWN PASSAGERS
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



   // ETAPE 2 RESERVATION
   showTripDetail = false;
   step = 1;                       // ← on initialise à l’étape 1
   openTripDetail() {
     this.showTripDetail = true;
     this.step = 1;                // repartir toujours à l’étape 1
   }
   closeTripDetail() {
     this.showTripDetail = false;
   }
   goToStep(n: number) {
    console.log('→ step =', n);
     this.step = n;
     
   }
   
}


