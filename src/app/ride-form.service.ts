import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class RideFormService {
  formData: {
    departure?: string;
    arrival?: string;
    date?: string;
    time?: string;
    vehicleId?: number;
    seats?: number;
    price?: number;
    comment?: string;
  } = {};

  reset() {
    this.formData = {};
  }
}
