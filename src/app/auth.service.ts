import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { HttpClient } from '@angular/common/http';

export interface User {
  id: number;
  email: string;
  role: 'admin' | 'driver' | 'employe' | 'passenger' | 'guest';
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private _isLoggedIn = new BehaviorSubject<boolean>(false);
  readonly isLoggedIn$ = this._isLoggedIn.asObservable();

  private userSubject = new BehaviorSubject<User | null>(null);
  readonly user$: Observable<User | null> = this.userSubject.asObservable();

  constructor(private http: HttpClient) {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      try {
        this.userSubject.next(JSON.parse(storedUser));
        this._isLoggedIn.next(true);
      } catch (e) {
        console.error('❌ Erreur de parsing user localStorage:', e);
        localStorage.removeItem('user');
      }
    }
  }

  login(credentials: { email: string; password: string }): Observable<{ token: string; user: User }> {
    return this.http.post<{ token: string, user: User }>(
      '/api/login',
      credentials
    ).pipe(
      tap(response => {
        const { token, user } = response;
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        this.userSubject.next(user);
        this._isLoggedIn.next(true);
      })
    );
  }
  

  logout() {
    this.userSubject.next(null);
    this._isLoggedIn.next(false);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  }

  register(data: { email: string; password: string; pseudo: string }): Observable<any> {
    return this.http.post('http://localhost:8000/api/register', data);
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }
}
