import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { RideService } from '../ride.service';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-profile-passenger',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './profile-passenger.component.html',
  styleUrl: './profile-passenger.component.scss'
})
export class ProfilePassengerComponent implements OnInit {
  rides: any[] = [];

  constructor(
    private authService: AuthService,
    private router: Router,
    private rideService: RideService
  ) {}

  ngOnInit(): void {
    this.rideService.getUserRides().subscribe({
      next: (res) => this.rides = res,
      error: (err) => console.error('Erreur chargement trajets :', err)
    });
  }

  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
