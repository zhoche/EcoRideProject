import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RideFormService } from '../ride-form.service';
import { FormsModule } from '@angular/forms';
import { MapComponent } from '../../map.component/map.component';

@Component({
  selector: 'app-new-ride-step-1',
  standalone: true,
  imports: [CommonModule, FormsModule, MapComponent],
  templateUrl: './new-ride-step-1.component.html',
  styleUrl: './new-ride-step-1.component.scss'
})
export class NewRideStep1Component {
  @Output() next = new EventEmitter<void>();


  constructor(public rideForm: RideFormService) {}

}
