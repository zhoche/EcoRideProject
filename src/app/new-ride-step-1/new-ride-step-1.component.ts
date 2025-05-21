import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-new-ride-step-1',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './new-ride-step-1.component.html',
  styleUrl: './new-ride-step-1.component.scss'
})
export class NewRideStep1Component {
  @Output() next = new EventEmitter<void>();

}
