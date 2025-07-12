export interface Ride {
    id: number;
    date: string;
    departureTime: string;
    arrivalTime: string;
    departureCity: string;
    arrivalCity: string;
    duration: string;
    price: number;
    availableSeats: number;
    driverName: string;
    driverImage: string;
    rating: number;
    verified: boolean;
    extras: string;
    driver?: any; 
    isElectric?: boolean;

  }