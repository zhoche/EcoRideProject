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


  //DETAILS VOYAGE
  showTripDetail = false;
  openTripDetail() { this.showTripDetail = true; }
  closeTripDetail() { this.showTripDetail = false; }




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
}


