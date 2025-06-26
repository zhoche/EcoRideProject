import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NewRideComponent } from '../new-ride/new-ride.component';
import { RideService } from '../ride.service';
import { RouterModule } from '@angular/router';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-profile-driver',
  standalone: true,
  imports: [
    CommonModule,
    NewRideComponent,
    RouterModule
  ],
  templateUrl: './profile-driver.component.html',
  styleUrls: ['./profile-driver.component.scss']
})
export class ProfileDriverComponent {
  showNewRideWizard = false;

  constructor(
    private authService: AuthService,
    private router: Router,
  ) {}

 
  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
