import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RideFormService } from '../ride-form.service';
import { RideService } from '../ride.service';


@Component({
  selector: 'app-new-ride-step-3',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './new-ride-step-3.component.html',
  styleUrl: './new-ride-step-3.component.scss'
})
export class NewRideStep3Component {
  @Output() back   = new EventEmitter<void>();
  @Output() finish = new EventEmitter<void>();

  fare = 4;   
  note = '';   

  decreaseFare() {
    if (this.fare > 0) {
      this.fare--;
    }
  }

  increaseFare() {
    this.fare++;
  }

  onSubmit() {
    this.rideForm.formData.price = this.fare; 
    this.rideForm.formData.comment = this.note;
  
    console.log('Formulaire envoyé :', this.rideForm.formData);

    this.rideService.createRide(this.rideForm.formData).subscribe({
      next: res => {
        alert('✅ Trajet créé avec succès');
        this.rideForm.reset();
        this.finish.emit();
      },
      error: err => {
        console.error(err);
        alert('Erreur création trajet : ' + (err.error?.error || 'inconnue'));
      }
    });
  }
  

  constructor(private rideService: RideService, public rideForm: RideFormService) {}



}
