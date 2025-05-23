import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap } from 'rxjs';          // RxJS 7+ : on peut importer tap depuis 'rxjs'
import { HttpClient } from '@angular/common/http';

export interface User {
  id:     string;
  email:  string;
  role:   'passenger' | 'driver' | 'employe' | 'admin';
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private _isLoggedIn  = new BehaviorSubject<boolean>(false);
  readonly isLoggedIn$ = this._isLoggedIn.asObservable();

  private userSubject  = new BehaviorSubject<User|null>(null);
  readonly user$: Observable<User|null> = this.userSubject.asObservable();

  constructor(private http: HttpClient) {
    const stored = localStorage.getItem('user');
    if (stored) {
      this.userSubject.next(JSON.parse(stored));
      this._isLoggedIn.next(true);  
    }
  }

  login(credentials: { email: string; password: string }): Observable<User> {
    return this.http.post<User>('/api/login', credentials)
      .pipe(
        tap(user => {
          this.userSubject.next(user);
          this._isLoggedIn.next(true);   
          localStorage.setItem('user', JSON.stringify(user));
        })
      );
  }

  logout() {
    this.userSubject.next(null);
    this._isLoggedIn.next(false);     
    localStorage.removeItem('user');
  }

  
}
