import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OngletSuspendedComponent } from './onglet-suspended.component';

describe('OngletSuspendedComponent', () => {
  let component: OngletSuspendedComponent;
  let fixture: ComponentFixture<OngletSuspendedComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [OngletSuspendedComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OngletSuspendedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
