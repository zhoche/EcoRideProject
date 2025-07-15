import { Component, OnInit } from '@angular/core';
import { RideService } from '../ride.service';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { NgChartsModule } from 'ng2-charts';
import { RideStateService } from '../ride-state.service'; 


@Component({
  selector: 'app-driver-dashboard',
  standalone: true,
  imports: [CommonModule, NgChartsModule],
  templateUrl: './driver-dashboard.component.html',
  styleUrl: './driver-dashboard.component.scss',
})
export class DriverDashboardComponent implements OnInit {
  credits: number = 0;
  rides: any[] = [];
  creditHistory: { date: string; value: number }[] = [];
  preferences: string[] = [];


  chartData: any;

  chartOptions = {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: { beginAtZero: true }
    }
  };

  constructor(
    private authService: AuthService,
    private router: Router,
    private rideService: RideService,
    private rideStateService: RideStateService
  ) {}

  ngOnInit(): void {
    this.rideService.getUserRides().subscribe({
      next: (res) => {
        this.credits = res.credits;
        this.rides = res.rides;
        this.creditHistory = res.creditHistory;
        this.preferences = res.preferences || [];


        this.chartData = {
          labels: this.creditHistory.map(e => e.date),
          datasets: [
            {
              label: 'Évolution des crédits',
              data: this.creditHistory.map(e => e.value),
              borderColor: '#6fb586',
              backgroundColor: 'rgba(46,125,50,0.2)',
              tension: 0.3,
              fill: true
            }
          ]
        };
      },
      error: (err) => console.error('Erreur chargement dashboard', err)
    });

  }


  onStartRide(rideId: number): void {
    this.rideStateService.startRide(rideId);
  }

  
  terminateRide(rideId: number): void {
    this.rideService.terminateRide(rideId).subscribe({
      next: () => {
        alert("Les passagers ont été notifiés.");
        this.rideStateService.resetRide(); // ← facultatif : cache les boutons dans le header
      },
      error: (err) => {
        console.error("Erreur de fin de trajet :", err);
        alert("Impossible de terminer le trajet.");
      }
    });
  }

  

  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
