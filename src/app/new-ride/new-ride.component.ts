import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewRideStep1Component } from '../new-ride-step-1/new-ride-step-1.component';
import { NewRideStep2Component } from '../new-ride-step-2/new-ride-step-2.component';

@Component({
  selector: 'app-new-ride',
  standalone: true,
  imports: [
    CommonModule,       
    NewRideStep1Component,
    NewRideStep2Component
  ],
  templateUrl: './new-ride.component.html',
  styleUrls: ['./new-ride.component.scss']
})
export class NewRideComponent {
  currentStep = 1;

  nextStep() {
    console.log('nextStep called, before =', this.currentStep);
    this.currentStep++;
    console.log('after =', this.currentStep);
  }

  prevStep() {
    this.currentStep--;
  }
}