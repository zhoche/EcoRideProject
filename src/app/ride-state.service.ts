import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RideStateService {
    private startedRideIdSubject = new BehaviorSubject<number | null>(null);
    startedRideId$ = this.startedRideIdSubject.asObservable();
  
    startRide(rideId: number) {
      this.startedRideIdSubject.next(rideId);
    }
  
    resetRide() {
      this.startedRideIdSubject.next(null);
    }
}
