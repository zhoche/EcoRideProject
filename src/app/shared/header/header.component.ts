import { Component, OnInit } from '@angular/core';
import { AuthService, User } from '../../auth.service';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive }   from '@angular/router';
import { RideStateService } from '../../ride-state.service';
import { RideService } from '../../ride.service';


@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive],  
  templateUrl: './header.component.html',
  styleUrl: './header.component.scss'
})
export class HeaderComponent implements OnInit {
  role: string = '';
  startedRideId: number | null = null;

  constructor(
    private authService: AuthService,
    private rideStateService: RideStateService,
    private rideService: RideService
  ) {}

  ngOnInit(): void {
    this.authService.user$.subscribe(user => {
      this.role = user?.role || '';
    });

    this.rideStateService.startedRideId$.subscribe((rideId: number | null) => {
      this.startedRideId = rideId;
    });

    
  }


  terminateRide(rideId: number) {
    this.rideService.terminateRide(rideId).subscribe({
      next: () => {
        alert("Les passagers ont été notifiés.");
      },
      error: (err) => {
        console.error("Erreur de fin de trajet :", err);
        alert("Impossible de terminer le trajet.");
      }
    });
  }
  }


