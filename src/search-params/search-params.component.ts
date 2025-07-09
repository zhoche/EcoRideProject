import { Component } from '@angular/core';
import { SearchParams } from './search-params.model'; 
import { RideService } from '../app/ride.service';
import { Ride } from '../app/ride.model';

@Component({
  selector: 'app-search-params',
  imports: [RideService],
  templateUrl: './search-params.component.html',
  styleUrl: './search-params.component.scss'
})
export class SearchParamsComponent {


  rides: Ride[] = [];

  
  constructor(private rideService: RideService) {}

  onSearch(params: SearchParams) {
    this.rideService.searchRides(params).subscribe({
      next: (rides) => {
        this.rides = rides.length ? rides : [];
        if (rides.length === 0) {
          this.loadNextAvailableRides(params);
        }
      },
      error: () => alert('Erreur lors de la recherche')
    });
  }
  
  loadNextAvailableRides(params: SearchParams) {
    this.rideService.searchNextAvailableRides(params).subscribe({
      next: (nextRides) => this.rides = nextRides,
      error: () => alert('Aucun trajet disponible.')
    });
  }


}
