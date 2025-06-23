import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewRideComponent } from '../new-ride/new-ride.component';
import { RideService } from '../ride.service';

@Component({
  selector: 'app-profile-driver',
  standalone: true,
  imports: [
    CommonModule,
    NewRideComponent,   
  ],
  templateUrl: './profile-driver.component.html',
  styleUrls: ['./profile-driver.component.scss']
})
export class ProfileDriverComponent implements OnInit {
  showNewRideWizard = true;
  rides: any[] = [];

  constructor(private rideService: RideService) {}

  ngOnInit(): void {
    this.rideService.getUserRides().subscribe({
      next: (res) => this.rides = res,
      error: (err) => console.error('Erreur chargement trajets :', err)
    });
  }

  onRideFinished() {
    this.showNewRideWizard = false;
  }
}
