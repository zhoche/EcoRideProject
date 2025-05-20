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



export const routes: Routes = [
    { path: 'home' , component: HomeComponent },
    { path: '' , redirectTo: '/home', pathMatch: 'full' },
    { path: 'connexion' , component: ConnexionComponent },
    { path: 'inscription' , component: InscriptionComponent },
    { path: 'recherche-covoit' , component: RechercheCovoitComponent },
    { path: 'modal' , component: ModalComponent },
    { path: 'profile-passenger' , component: ProfilePassengerComponent },
    { path: 'ride-validated' , component: RideValidatedComponent },
]
