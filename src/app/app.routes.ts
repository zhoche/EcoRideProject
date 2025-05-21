import { Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { ConnexionModule } from './connexion/connexion.module';
import { ConnexionComponent } from './connexion/connexion.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InscriptionModule } from './inscription/inscription.module';
import { RechercheCovoitModule } from './recherche-covoit/recherche-covoit.module';
import { RechercheCovoitComponent } from './recherche-covoit/recherche-covoit.component';
import { ModalComponent } from './modal/modal.component';
import { ProfilePassengerModule } from './profile-passenger/profile-passenger.module';
import { ProfilePassengerComponent } from './profile-passenger/profile-passenger.component';
import { RideValidatedModule } from './ride-validated/ride-validated.module';
import { RideValidatedComponent } from './ride-validated/ride-validated.component';
import { RideReportModule } from './ride-report/ride-report.module';
import { RideReportComponent } from './ride-report/ride-report.component';
import { ProfileDriverModule } from './profile-driver/profile-driver.module';
import { ProfileDriverComponent } from './profile-driver/profile-driver.component';
import { ProfileEmployeModule } from './profile-employe/profile-employe.module';
import { ProfileEmployeComponent } from './profile-employe/profile-employe.component';
import { ProfileAdminModule } from './profile-admin/profile-admin.module';
import { ProfileAdminComponent } from './profile-admin/profile-admin.component';



export const routes: Routes = [
    { path: 'home' , component: HomeComponent },
    { path: '' , redirectTo: '/home', pathMatch: 'full' },
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
]
