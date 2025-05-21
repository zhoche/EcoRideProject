import { ComponentFixture, TestBed } from '@angular/core/testing';

import { NewRideStep1Component } from './new-ride-step-1.component';

describe('NewRideStep1Component', () => {
  let component: NewRideStep1Component;
  let fixture: ComponentFixture<NewRideStep1Component>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [NewRideStep1Component]
    })
    .compileComponents();

    fixture = TestBed.createComponent(NewRideStep1Component);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
