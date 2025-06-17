import { Injectable } from '@angular/core';
import {
  CanActivate,
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  UrlTree,
  Router
} from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../auth.service';
import { map } from 'rxjs/operators';

@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {

  constructor(private auth: AuthService, private router: Router) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean | UrlTree> {
    const expectedRoles: string[] = route.data['roles'] || [];

    return this.auth.user$.pipe(
      map(user => {
        if (!user) {
          return this.router.createUrlTree(['/connexion']);
        }

        if (expectedRoles.length && !expectedRoles.includes(user.role)) {
          return this.router.createUrlTree(['/unauthorized']); // ou autre
        }

        return true;
      })
    );
  }
}
