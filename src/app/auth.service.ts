import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { switchMap } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { environment } from '../environments/environment';

export interface User {
  id: number;
  email: string;
  role: 'admin' | 'driver' | 'employe' | 'passenger' | 'guest';
  pseudo: string;
  imageUrl: string | null;
  isVerified: boolean;
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private _isLoggedIn = new BehaviorSubject<boolean>(false);
  readonly isLoggedIn$ = this._isLoggedIn.asObservable();

  private userSubject = new BehaviorSubject<User | null>(null);
  readonly user$ = this.userSubject.asObservable();

  private readonly base = `${environment.apiUrl}/api`;

  constructor(private http: HttpClient, private router: Router) {
    const storedUser = localStorage.getItem('user');
    if (storedUser && storedUser !== 'undefined') {
      try {
        this.userSubject.next(JSON.parse(storedUser));
        this._isLoggedIn.next(true);
      } catch {
        localStorage.removeItem('user');
      }
    } else if (this.getToken()) {
      // si on a un token mais pas d'user en localStorage, on le recharge
      this.loadCurrentUser().subscribe();
    }
  }

  /** Récupère le user complet */
  loadCurrentUser(): Observable<User> {
    return this.http.get<User>(`${this.base}/me`).pipe(
      tap(user => {
        this.userSubject.next(user);
        this._isLoggedIn.next(true);
        localStorage.setItem('user', JSON.stringify(user));
      })
    );
  }

  /**
   * Se logue, stocke le token, puis fetch le user complet
   */
  login(credentials: { email: string; password: string }): Observable<User> {
    return this.http
      .post<{ token: string }>(`${this.base}/login`, credentials)
      .pipe(
        tap(res => localStorage.setItem('token', res.token)),
        switchMap(() => this.loadCurrentUser())
      );
  }

  logout(): void {
    this.userSubject.next(null);
    this._isLoggedIn.next(false);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.router.navigate(['/connexion']);
  }

  register(data: {
    email: string;
    password: string;
    pseudo: string;
    roles: string[];
    gender: string;
  }): Observable<any> {
    return this.http.post<any>(`${this.base}/register`, data);
  }
  
  getToken(): string | null {
    return localStorage.getItem('token');
  }

  redirectUserAfterLogin(): void {
    const user = this.userSubject.value;
    if (!user) return;
    this.router.navigate([`/profile-${user.role}`]);
  }
}
