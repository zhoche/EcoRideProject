import { Injectable } from '@angular/core';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { JwtPayload, jwtDecode } from 'jwt-decode';



export interface User {
  id: number;
  email: string;
  role: 'admin' | 'driver' | 'employe' | 'passenger' | 'guest';
}


interface CustomJwtPayload {
  id: number;
  email: string;
  roles: string[];
}


@Injectable({ providedIn: 'root' })
export class AuthService {
  private _isLoggedIn = new BehaviorSubject<boolean>(false);
  readonly isLoggedIn$ = this._isLoggedIn.asObservable();

  private userSubject = new BehaviorSubject<User | null>(null);
  readonly user$: Observable<User | null> = this.userSubject.asObservable();

  constructor(private http: HttpClient, private router: Router) {
    const storedUser = localStorage.getItem('user');
    if (storedUser && storedUser !== 'undefined') {
      try {
        this.userSubject.next(JSON.parse(storedUser));
        this._isLoggedIn.next(true);
      } catch (e) {
        console.error('Erreur de parsing user localStorage:', e);
        localStorage.removeItem('user'); 
      }
    }
    
  }

  login(credentials: { email: string; password: string }): Observable<{ token: string }> {
    return this.http.post<{ token: string }>(
      'https://ecoride-back-xm7y.onrender.com/api/login',
      credentials
    ).pipe(
      tap(response => {
        const { token } = response; 
        const payload: CustomJwtPayload = jwtDecode(token);
  
        const role = this.extractRole(payload.roles);
        const user: User = {
          id: payload.id,
          email: payload.email,
          role
        };
  
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
        this.userSubject.next(user);
        this._isLoggedIn.next(true);
      })
    );
  }
  
  private extractRole(roles: string[]): User['role'] {
    if (roles.includes('ROLE_ADMIN')) return 'admin';
    if (roles.includes('ROLE_DRIVER')) return 'driver';
    if (roles.includes('ROLE_EMPLOYE')) return 'employe';
    if (roles.includes('ROLE_USER')) return 'passenger';
    return 'guest';
  }

  
  redirectUserAfterLogin(): void {
    const user = this.userSubject.value;
  
    if (!user) {
      console.error("Utilisateur non connecté");
      return;
    }
  
    switch (user?.role) {
      case 'passenger':
        this.router.navigate(['/profile-passenger']);
        break;
      case 'driver':
        this.router.navigate(['/profile-driver']);
        break;
      case 'employe':
        this.router.navigate(['/profile-employe']);
        break;
      case 'admin':
        this.router.navigate(['/profile-admin']);
        break;
      default:
        this.router.navigate(['/']);
    }
  }
  

  logout() {
    this.userSubject.next(null);
    this._isLoggedIn.next(false);
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  }

  register(data: { email: string; password: string; pseudo: string; roles: string[]; gender: string; }): Observable<any> {
    return this.http.post('https://ecoride-back-xm7y.onrender.com/api/register', data);
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }
}
