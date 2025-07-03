import { Component, OnInit } from '@angular/core';
import { AdminService } from '../admin.service';
import { NgChartsModule } from 'ng2-charts';
import { ChartConfiguration, ChartOptions } from 'chart.js';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-profile-admin',
  standalone: true,
  imports: [NgChartsModule],
  templateUrl: './profile-admin.component.html',
  styleUrls: ['./profile-admin.component.scss']
})
export class ProfileAdminComponent implements OnInit {

  totalCredits: number = 0;

  ridesChartLabels: string[] = [];
  ridesChartData: number[] = [];

  chartData: ChartConfiguration<'line'>['data'] = {
    labels: [],
    datasets: [
      {
        data: [],
        label: 'Nombre de covoiturages',
        fill: true,
        tension: 0.4
      }
    ]
  };

  creditsChartConfig = {
    data: {
      labels: [] as string[],
      datasets: [
        {
          data: [] as number[],
          label: 'Crédits gagnés',
          backgroundColor: '#4CAF50'
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    } as ChartOptions<'bar'>
  };

  chartOptions: ChartOptions<'line'> = {
    responsive: true,
  };

  constructor(
    private adminService: AdminService,
      private authService: AuthService,
      private router: Router
  ) {}



  ngOnInit(): void {
    this.adminService.getTotalCredits().subscribe(data => {
      this.totalCredits = data.totalCredits;
    });
  
    // Nombre de covoiturages par jour
    this.adminService.getRidesPerDay().subscribe(data => {
      this.chartData = {
        labels: data.map(d => d.date),
        datasets: [
          {
            data: data.map(d => d.total),
            label: 'Nombre de covoiturages',
            fill: true,
            tension: 0.4
          }
        ]
      };
    });
  
    // Crédits gagnés par jour
    this.adminService.getCreditsPerDay().subscribe(data => {
      this.creditsChartConfig.data.labels = data.map(d => d.date);
      this.creditsChartConfig.data.datasets[0].data = data.map(d => d.credits);
    });
  }
  

  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }

}
