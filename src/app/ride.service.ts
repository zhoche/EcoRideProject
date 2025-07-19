import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';
import { SearchParams } from '../search-params/search-params.model';
import { Ride } from './ride.model';

@Injectable({ providedIn: 'root' })
export class RideService {
  constructor(
    private http: HttpClient,
    private authService: AuthService
  ) {}

  getUserRides() {
    const token = this.authService.getToken();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    return this.http.get<{ 
      credits: number; 
      rides: any[]; 
      preferences: string[];
      creditHistory: { date: string; value: number }[];
    }>('https://ecoride-back-xm7y.onrender.com/api/rides/list', {
      headers,
      withCredentials: false 
    });
  }


  createRide(data: any) {
    const token = this.authService.getToken();
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  
    return this.http.post(
      'https://ecoride-back-xm7y.onrender.com/api/rides/new-ride',
      data,
      { headers }
    );
  }


  searchRides(params: {
    villeDepart: string;
    villeArrivee: string;
    date: string;
    nbPassagers: number;
  }) {
    return this.http.get<any[]>('https://ecoride-back-xm7y.onrender.com/api/rides/search', {
      params: {
        villeDepart: params.villeDepart,
        villeArrivee: params.villeArrivee,
        date: params.date,
        nbPassagers: params.nbPassagers.toString()
      }
    });
  }


  searchNextAvailableRides(params: SearchParams) {
    return this.http.get<Ride[]>('https://ecoride-back-xm7y.onrender.com/api/rides/next-available', {
      params: {
        villeDepart: params.villeDepart,
        villeArrivee: params.villeArrivee,
        date: params.date,
        nbPassagers: params.nbPassagers.toString()
      }
    });
  }



  terminateRide(rideId: number) {
    return this.http.post(`/api/rides/${rideId}/terminate`, {});
  }
}
