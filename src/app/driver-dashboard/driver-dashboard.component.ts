import { Component, OnInit } from '@angular/core';
import { RideService } from '../ride.service';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-driver-dashboard',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './driver-dashboard.component.html',
  styleUrl: './driver-dashboard.component.scss',
})
export class DriverDashboardComponent implements OnInit {
  rides: any[] = [];

  constructor(
    private rideService: RideService,
    private authService: AuthService,
    private router: Router
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
