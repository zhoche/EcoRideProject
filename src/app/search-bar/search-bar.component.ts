import { Component, EventEmitter, Output, Input } from '@angular/core';
import { SearchParams } from '../../search-params/search-params.model';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-search-bar',
  templateUrl: './search-bar.component.html',
  styleUrls: ['./search-bar.component.scss'],
  imports: [CommonModule, FormsModule],
  standalone: true
})
export class SearchBarComponent {
  @Input() villeDepart = '';
  @Input() villeArrivee = '';
  @Input() date = '';
  @Input() nbPassagers = 1;

  @Output() search = new EventEmitter<SearchParams>();

  constructor(private router: Router) {}

  onSearchClick() {
    if (!this.villeDepart || !this.villeArrivee || !this.date) {
      alert("Merci de remplir tous les champs de recherche.");
      return;
    }

    const params: SearchParams = {
      villeDepart: this.villeDepart,
      villeArrivee: this.villeArrivee,
      date: this.date,
      nbPassagers: this.nbPassagers
    };

    this.search.emit(params);

    // Navigation uniquement sur la home
    if (this.router.url === '/home') {
      this.router.navigate(['/recherche-covoit'], { state: params });
    }
  }

  onVilleDepartChange(value: string) {
    this.villeDepart = this.capitalizeInput(value);
  }
  
  onVilleArriveeChange(value: string) {
    this.villeArrivee = this.capitalizeInput(value);
  }
  
  capitalizeInput(value: string): string {
    if (!value) return '';
    return value.trim().charAt(0).toUpperCase() + value.trim().slice(1);
  }
  
}
