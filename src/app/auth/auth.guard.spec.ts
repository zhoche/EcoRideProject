import { TestBed } from '@angular/core/testing';
import { Router, UrlTree, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { of } from 'rxjs';

import { AuthGuard } from './auth.guard';
import { AuthService } from '../auth.service';

describe('AuthGuard', () => {
  let guard: AuthGuard;
  let authServiceSpy: jasmine.SpyObj<AuthService>;
  let routerSpy: jasmine.SpyObj<Router>;

  beforeEach(() => {
    // 1) Crée des spies pour AuthService et Router
    authServiceSpy = jasmine.createSpyObj('AuthService', [], {
      user$: of(null)    // par défaut : pas de user connecté
    });
    routerSpy = jasmine.createSpyObj('Router', ['createUrlTree']);

    // 2) Configure TestBed pour injecter AuthGuard avec ses dépendances
    TestBed.configureTestingModule({
      providers: [
        AuthGuard,
        { provide: AuthService, useValue: authServiceSpy },
        { provide: Router,      useValue: routerSpy      }
      ]
    });

    // 3) Récupère l'instance réelle du guard
    guard = TestBed.inject(AuthGuard);
  });

  it('devrait être créé', () => {
    expect(guard).toBeTruthy();
  });

  it('redirige vers /connexion si pas d’utilisateur', (done) => {
    const fakeTree = {} as UrlTree;
    routerSpy.createUrlTree.and.returnValue(fakeTree);

    guard.canActivate(
      { data: {} } as ActivatedRouteSnapshot,
      {} as RouterStateSnapshot
    ).subscribe(result => {
      expect(routerSpy.createUrlTree).toHaveBeenCalledWith(['/connexion']);
      expect(result).toBe(fakeTree);
      done();
    });
  });

  it('autorise si user a le rôle attendu', (done) => {
    // Simule un user connecté
    (authServiceSpy.user$ as any) = of({ id:1, email:'a', role:'admin' });
  
    // Crée un faux ActivatedRouteSnapshot qui ne contient que data
    const fakeRoute = {
      data: { roles: ['admin'] }
    } as unknown as ActivatedRouteSnapshot;
  
    // Un RouterStateSnapshot minimal
    const fakeState = { url: '/fakestate' } as RouterStateSnapshot;
  
    guard.canActivate(fakeRoute, fakeState)
      .subscribe(result => {
        expect(result).toBe(true);
        done();
      });
  });

  it('redirige vers /unauthorized si rôle incorrect', (done) => {
    (authServiceSpy.user$ as any) = of({ id:1, email:'a', role:'passenger' });
    const fakeTree = {} as UrlTree;
    routerSpy.createUrlTree.and.returnValue(fakeTree);
  
    const fakeRoute = {
      data: { roles: ['driver'] }
    } as unknown as ActivatedRouteSnapshot;
    const fakeState = { url: '/fakestate' } as RouterStateSnapshot;
  
    guard.canActivate(fakeRoute, fakeState)
      .subscribe(result => {
        expect(routerSpy.createUrlTree).toHaveBeenCalledWith(['/unauthorized']);
        expect(result).toBe(fakeTree);
        done();
      });
  });
});
