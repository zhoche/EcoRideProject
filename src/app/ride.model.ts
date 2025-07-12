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
  extras?: string;
  isElectric?: boolean;
  driver: {
    pseudo: string;
    image: string;
    rating: number;
    verified: boolean;
    gender: string;
  };
}
