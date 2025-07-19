import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';

@Component({
  selector: 'app-onglet-suspended',
  standalone: true,
  templateUrl: './onglet-suspended.component.html',
  styleUrls: ['./onglet-suspended.component.scss'],
  imports: [CommonModule, ReactiveFormsModule, HttpClientModule]
})
export class OngletSuspendedComponent implements OnInit {
  suspendedForm!: FormGroup;
  suspendedAccounts: any[] = [];

  constructor(
    private fb: FormBuilder,
    private http: HttpClient
  ) {}

  ngOnInit(): void {
    this.suspendedForm = this.fb.group({
      pseudo: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      confirmEmail: ['', [Validators.required, Validators.email]]
    });

    this.fetchSuspendedAccounts();
  }

  onSubmit(): void {
    if (this.suspendedForm.invalid) return;

    const { pseudo, email, confirmEmail } = this.suspendedForm.value;

    if (email !== confirmEmail) {
      alert('❌ Les emails ne correspondent pas.');
      return;
    }

    const payload = { pseudo, email };

    this.http.post('https://ecoride-back-xm7y.onrender.com/api/admin/suspended-user', payload, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    }).subscribe({
      next: () => {
        alert('✅ Compte suspendu avec succès.');
        this.suspendedForm.reset();
        this.fetchSuspendedAccounts();

      },
      error: err => {
        console.error('Erreur lors de la suspension du compte', err);
        alert('❌ Une erreur est survenue.');
      }
    });
  }

  fetchSuspendedAccounts(): void {
    this.http.get<any[]>('https://ecoride-back-xm7y.onrender.com/api/admin/suspended-users-list', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    }).subscribe({
      next: data => {
        this.suspendedAccounts = data;
      },
      error: err => {
        console.error('Erreur lors du chargement des comptes suspendus', err);
      }
    });
  }  
  
}
