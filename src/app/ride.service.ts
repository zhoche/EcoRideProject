import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AuthService } from './auth.service';

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

    return this.http.get<any[]>('http://localhost:8000/api/rides/list', {
      headers,
      withCredentials: false 
    });
  }
}
