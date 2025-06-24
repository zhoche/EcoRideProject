import { Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { ConnexionComponent } from './connexion/connexion.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { RechercheCovoitComponent } from './recherche-covoit/recherche-covoit.component';
import { ModalComponent } from './modal/modal.component';
import { ProfilePassengerComponent } from './profile-passenger/profile-passenger.component';
import { RideValidatedComponent } from './ride-validated/ride-validated.component';
import { RideReportComponent } from './ride-report/ride-report.component';
import { ProfileDriverComponent } from './profile-driver/profile-driver.component';
import { ProfileEmployeComponent } from './profile-employe/profile-employe.component';
import { ProfileAdminComponent } from './profile-admin/profile-admin.component';
import { NewRideComponent } from './new-ride/new-ride.component';
import { Error404Component } from './error-404/error-404.component';
import { HeaderComponent } from './shared/header/header.component';
import { FooterComponent } from './footer/footer.component';
import { AuthGuard } from './auth/auth.guard';


export const routes: Routes = [
    { path: '' , redirectTo: '/home', pathMatch: 'full' },
    { path: 'home' , component: HomeComponent },
    { path: 'connexion' , component: ConnexionComponent },
    { path: 'inscription' , component: InscriptionComponent },
    { path: 'recherche-covoit' , component: RechercheCovoitComponent },
    { path: 'modal' , component: ModalComponent },
    { path: 'profile-passenger' , component: ProfilePassengerComponent },
    { path: 'ride-validated' , component: RideValidatedComponent },
    { path: 'ride-report' , component: RideReportComponent },
    { path: 'profile-driver' , component: ProfileDriverComponent },
    { path: 'profile-employe' , component: ProfileEmployeComponent },
    { path: 'profile-admin' , component: ProfileAdminComponent },
    { path: 'new-ride' , component: NewRideComponent },
    { path: '**' , component: Error404Component },
    { path: 'profile-admin', component: ProfileAdminComponent, canActivate: [AuthGuard], data: { roles: ['admin'] }},
    { path: 'profile-driver', component: ProfileDriverComponent, canActivate: [AuthGuard], data: { roles: ['driver'] }},
    { path: 'profile-employe', component: ProfileEmployeComponent, canActivate: [AuthGuard], data: { roles: ['employe'] }}
]
