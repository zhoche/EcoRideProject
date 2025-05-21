import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewRideStep2Component } from './new-ride-step-2.component';

describe('NewRideStep2Component', () => {
  let component: NewRideStep2Component;
  let fixture: ComponentFixture<NewRideStep2Component>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NewRideStep2Component]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewRideStep2Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
