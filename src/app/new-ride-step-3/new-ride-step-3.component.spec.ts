import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewRideStep3Component } from './new-ride-step-3.component';

describe('NewRideStep3Component', () => {
  let component: NewRideStep3Component;
  let fixture: ComponentFixture<NewRideStep3Component>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NewRideStep3Component]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewRideStep3Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
