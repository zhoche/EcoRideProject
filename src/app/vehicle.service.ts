import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable, isStandalone } from '@angular/core';
import { AuthService } from './auth.service';


@Injectable({ providedIn: 'root' })
export class VehicleService {
  constructor(private http: HttpClient, private auth: AuthService) {}

  getUserVehicles() {
    const token = this.auth.getToken();
    const headers = new HttpHeaders({
      Authorization: `Bearer ${token}`
    });

    return this.http.get<any[]>('https://ecoride-back-xm7y.onrender.com/api/vehicles/user', { headers });
  }
}
