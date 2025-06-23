import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class RideService {
  constructor(private http: HttpClient) {}

  getUserRides() {
    const token = localStorage.getItem('token');
    return this.http.get<any[]>('/api/rides/list', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
  }
}