import { Component, OnInit } from '@angular/core';
import { RideService } from '../ride.service';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { NgChartsModule } from 'ng2-charts';

@Component({
  selector: 'app-profile-passenger',
  standalone: true,
  imports: [CommonModule, NgChartsModule],
  templateUrl: './profile-passenger.component.html',
  styleUrl: './profile-passenger.component.scss',
})
export class ProfilePassengerComponent implements OnInit {
  credits: number = 0;
  rides: any[] = [];
  creditHistory: { date: string; value: number }[] = [];

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
    private rideService: RideService
  ) {}

  ngOnInit(): void {
    this.rideService.getUserRides().subscribe({
      next: (res) => {
        this.credits = res.credits;
        this.rides = res.rides;
        this.creditHistory = res.creditHistory;

        this.chartData = {
          labels: this.creditHistory.map(e => e.date),
          datasets: [
            {
              label: 'Évolution des crédits',
              data: this.creditHistory.map(e => e.value),
              borderColor: '#1976d2',
              backgroundColor: 'rgba(25, 118, 210, 0.3)',
              tension: 0.3,
              fill: true
            }
          ]
        };
      },
      error: (err) => console.error('Erreur chargement passager', err)
    });
  }

  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
