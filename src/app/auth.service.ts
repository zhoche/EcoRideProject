import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export interface User {
  id: string;
  email: string;
  // …les champs que ton API renvoie
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  // État interne : on garde aussi le BehaviorSubject si tu veux propager l'état
  private _isLoggedIn = new BehaviorSubject<boolean>(false);
  readonly isLoggedIn$ = this._isLoggedIn.asObservable();

  constructor(private http: HttpClient) {}

  /**
   * Appelle ton API pour te logguer.
   * Si c'est réussi, on passe _isLoggedIn à true.
   */
  login(email: string, password: string): Observable<User> {
    return new Observable<User>(subscriber => {
      this.http.post<User>('/api/auth/login', { email, password })
        .subscribe({
          next: user => {
            // on notifie qu’on est connecté
            this._isLoggedIn.next(true);
            subscriber.next(user);
            subscriber.complete();
          },
          error: err => subscriber.error(err)
        });
    });
  }

  /** Pour déconnecter */
  logout(): void {
    this._isLoggedIn.next(false);
    // tu peux aussi appeler ton API de logout ici
  }
}
