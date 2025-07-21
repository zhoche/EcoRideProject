import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from '../environments/environment';
import { AuthService } from './auth.service';
import { SearchParams } from '../search-params/search-params.model';
import { Ride } from './ride.model';
import { map } from 'rxjs/operators';



function stripImages(path: string): string {
  return path.replace(/^(\/?images\/)+/, '');
}


@Injectable({ providedIn: 'root' })
export class RideService {

  private api = `${environment.apiUrl}/api`;



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
    }>(`${this.api}/rides/list`, {
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
      `${this.api}/rides/new-ride`,
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
    return this.http
      .get<Ride[]>(`${this.api}/rides/search`, {
        params: {
          villeDepart: params.villeDepart,
          villeArrivee: params.villeArrivee,
          date: params.date,
          nbPassagers: params.nbPassagers.toString()
        }
      })
      .pipe(
      map(rides =>
        rides.map(r => {
          const raw = stripImages(r.driver.image);
          return {
            ...r,
            driver: {
              ...r.driver,
              image: `/images/${raw}`
            }
          };
        })
      )
    );
  }


  searchNextAvailableRides(params: SearchParams) {
    return this.http
      .get<Ride[]>(`${this.api}/rides/next-available`, {
        params: {
          villeDepart: params.villeDepart,
          villeArrivee: params.villeArrivee,
          date: params.date,
          nbPassagers: params.nbPassagers.toString()
        }
      })
      .pipe(
        map(rides =>
          rides.map(r => {
            const raw = stripImages(r.driver.image);
            return {
              ...r,
              driver: {
                ...r.driver,
                image: `/images/${raw}`
              }
            };
          })
        )
      );
  }



  terminateRide(rideId: number) {
    return this.http.post(`${this.api}/rides/${rideId}/terminate`, {});
  }
}
