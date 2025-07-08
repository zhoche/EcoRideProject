import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewRideStep1Component } from '../new-ride-step-1/new-ride-step-1.component';
import { NewRideStep2Component } from '../new-ride-step-2/new-ride-step-2.component';
import { NewRideStep3Component } from '../new-ride-step-3/new-ride-step-3.component';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-new-ride',
  standalone: true,
  imports: [
    CommonModule,       
    NewRideStep1Component,
    NewRideStep2Component,
    NewRideStep3Component
  ],
  templateUrl: './new-ride.component.html',
  styleUrls: ['./new-ride.component.scss']
})
export class NewRideComponent {
  currentStep = 1;
  @Output() finish = new EventEmitter<void>();

  nextStep() { if (this.currentStep < 3) this.currentStep++; }
  prevStep() { if (this.currentStep > 1) this.currentStep--; }

  onFinish() {
    alert('✅ Votre trajet a bien été créé !');
    this.finish.emit();

  }
  
}