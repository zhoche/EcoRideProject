import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AdminService {

  constructor(private http: HttpClient) {}

  getTotalCredits(): Observable<{ totalCredits: number }> {
    return this.http.get<{ totalCredits: number }>('/api/admin/credits-earned-total');
  }

  getCreditsPerDay(): Observable<{ date: string, credits: number }[]> {
    return this.http.get<{ date: string, credits: number }[]>('/api/admin/credits-earned-per-day');
  }

  getRidesPerDay(): Observable<{ date: string, total: number }[]> {
    return this.http.get<{ date: string, total: number }[]>('/api/admin/rides-per-day');
  }
}
