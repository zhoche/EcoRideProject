import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../environments/environment';

interface TotalCredits { totalCredits: number; }
interface DailyCredits { date: string; credits: number; }
interface DailyRides   { date: string; total: number; }

@Injectable({ providedIn: 'root' })
export class AdminService {
  // <-- ici on récupère l'URL de l'API depuis les environment
  private api = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getTotalCredits(): Observable<TotalCredits> {
    return this.http.get<TotalCredits>(
      `${this.api}/api/admin/credits-earned-total`
    );
  }

  getCreditsPerDay(): Observable<DailyCredits[]> {
    return this.http.get<DailyCredits[]>(
      `${this.api}/api/admin/credits-earned-per-day`
    );
  }

  getRidesPerDay(): Observable<DailyRides[]> {
    return this.http.get<DailyRides[]>(
      `${this.api}/api/admin/rides-per-day`
    );
  }
}
