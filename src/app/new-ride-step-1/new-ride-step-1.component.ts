import { Component, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-new-ride-step-1',
  standalone: true,
  imports: [],
  templateUrl: './new-ride-step-1.component.html',
  styleUrl: './new-ride-step-1.component.scss'
})
export class NewRideStep1Component {
  @Output() next = new EventEmitter<void>();

}
