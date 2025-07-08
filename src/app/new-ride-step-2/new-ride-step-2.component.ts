import { Component, Output, EventEmitter, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RideFormService } from '../ride-form.service';
import { VehicleService } from '../vehicle.service';

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
export class NewRideStep2Component implements OnInit {
  @Output() back = new EventEmitter<void>();
  @Output() next = new EventEmitter<void>();

  vehicles: Vehicle[] = [];
  seats: number[] = [1, 2, 3, 4, 5];

  selectedVehicleId: string | null = null;
  selectedSeats: number | null = null;
  isElectric: boolean | null = null;

  constructor(
    public rideForm: RideFormService,
    private vehicleService: VehicleService
  ) {}

  ngOnInit(): void {
    this.vehicleService.getUserVehicles().subscribe((vehicles) => {
      this.vehicles = vehicles;
    });
  }

  toggleElectric(value: boolean) {
    this.isElectric = value;
  }

  trackById(_idx: number, item: Vehicle): string {
    return item.id;
  }

  trackByIndex(idx: number): number {
    return idx;
  }
}
