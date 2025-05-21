import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-new-ride-step-3',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './new-ride-step-3.component.html',
  styleUrl: './new-ride-step-3.component.scss'
})
export class NewRideStep3Component {
  @Output() back   = new EventEmitter<void>();
  @Output() finish = new EventEmitter<void>();

  fare = 4;      // tarif initial
  note = '';     // bindé au textarea

  decreaseFare() {
    if (this.fare > 0) {
      this.fare--;
    }
  }

  increaseFare() {
    this.fare++;
  }

  /** appelé quand on clique sur “Valider le nouveau trajet” */
  onSubmit() {
    // ici vous feriez éventuellement votre appel API pour créer le ride…
    // puis on prévient le parent :
    this.finish.emit();
  }
}
