import { Component }      from '@angular/core';
import { CommonModule }   from '@angular/common';
import { NewRideComponent } from '../new-ride/new-ride.component';

@Component({
  selector: 'app-profile-driver',
  standalone: true,
  imports: [
    CommonModule,
    NewRideComponent,    // <-- votre wizard
  ],
  templateUrl: './profile-driver.component.html',
  styleUrls: ['./profile-driver.component.scss']
})
export class ProfileDriverComponent {
  showNewRideWizard = true;

  onRideFinished() {
    // une fois qu'on émet finish, on masque le wizard
    this.showNewRideWizard = false;
  }
}