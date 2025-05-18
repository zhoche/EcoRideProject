import { Component } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';
import { ModalComponent } from '../modal/modal.component';


@Component({
  selector: 'app-recherche-covoit',
  imports: [SearchBarComponent, ModalComponent],
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
}


