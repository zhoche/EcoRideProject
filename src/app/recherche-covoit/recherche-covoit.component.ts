import { Component } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';


@Component({
  selector: 'app-recherche-covoit',
  imports: [SearchBarComponent],
  templateUrl: './recherche-covoit.component.html',
  styleUrl: './recherche-covoit.component.scss'
})
export class RechercheCovoitComponent {
  showFilters = false;

  toggleFilters() {
    this.showFilters = !this.showFilters;
  }
}


