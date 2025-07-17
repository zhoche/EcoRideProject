// src/app/services/user-api.service.ts
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

interface RedirectRoleResponse {
  role: string;
  redirectTo: string;
}

interface AverageRatingResponse {
  driverId: number;
  averageRating: number;
}

@Injectable({ providedIn: 'root' })
export class UserApiService {
  // Préfixe général pour toutes les routes User
  private prefix = '/api';

  constructor(private api: ApiService) {}

  /**
   * Récupère le rôle de l'utilisateur connecté et la route de redirection correspondante.
   * GET /api/redirect-role
   */
  getRedirectRole(): Observable<RedirectRoleResponse> {
    return this.api.get<RedirectRoleResponse>(`${this.prefix}/redirect-role`);
  }

  /**
   * Récupère la note moyenne du driver identifié par son id.
   * GET /api/users/{id}/average-rating
   * @param userId L'identifiant du driver
   */
  getDriverAverageRating(userId: number): Observable<AverageRatingResponse> {
    return this.api.get<AverageRatingResponse>(
      `${this.prefix}/users/${userId}/average-rating`
    );
  }
}
