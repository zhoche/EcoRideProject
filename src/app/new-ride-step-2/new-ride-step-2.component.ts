import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

interface Vehicle {
  id: string;
  label: string;
}

@Component({
  selector: 'app-new-ride-step-2',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule
  ],
  templateUrl: './new-ride-step-2.component.html',
  styleUrls: ['./new-ride-step-2.component.scss']
})
export class NewRideStep2Component {
  // événements vers le parent
  @Output() back = new EventEmitter<void>();
  @Output() next = new EventEmitter<void>();

  // VEHICULE OPTIONS
  vehicles: Vehicle[] = [
    { id: 'veh1', label: 'Peugeot 208 (AB-123-CD)' },
    { id: 'veh2', label: 'Renault Clio (EF-456-GH)' },
    { id: 'veh3', label: 'Tesla Model 3 (TES-LA01)' },
  ];

  seats: number[] = [1, 2, 3, 4, 5];

  // variables de liaison
  selectedVehicleId: string | null = null;
  selectedSeats: number | null = null;
  isElectric: boolean | null = null;

  // trackBy pour optimiser *ngFor
  trackById(_idx: number, item: Vehicle): string {
    return item.id;
  }
  trackByIndex(idx: number): number {
    return idx;
  }

  // méthode pour basculer l’état électrique
  toggleElectric(value: boolean) {
    this.isElectric = value;
  }
}
