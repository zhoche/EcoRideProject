import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReviewService {
  private baseUrl = 'http://localhost:8000/api/employe';

  constructor(private http: HttpClient) {}

  getPendingReviews(): Observable<any[]> {
    return this.http.get<any[]>(`${this.baseUrl}/avis/a-traiter`);
  }
  
  getArchivedReviews(): Observable<any[]> {
    return this.http.get<any[]>(`${this.baseUrl}/avis/historique`);
  }

  authorizeFeedback(id: number, action: 'approve' | 'reject'): Observable<any> {
    return this.http.patch(`${this.baseUrl}/feedback/authorization`, {
      avis_id: id,
      action: action
    });
  }
}
