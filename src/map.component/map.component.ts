import { Component, AfterViewInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import * as L from 'leaflet';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { environment } from '../environments/environment';

@Component({
  selector: 'app-map',
  template: `<div id="map" style="height: 600px;"></div>`,
  standalone: true,
  imports: [HttpClientModule],
})
export class MapComponent implements AfterViewInit, OnChanges {
  @Input() departure!: string;
  @Input() arrival!: string;

  private map?: L.Map;
  private routeLayer?: L.GeoJSON;

  constructor(private http: HttpClient) {}

  async ngAfterViewInit() {
    this.map = L.map('map').setView([43.6, 1.44], 9); 
  
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap'
    }).addTo(this.map);
  
    if (this.departure && this.arrival) {
      const coords = await this.fetchCoordinates(this.departure, this.arrival);
      if (coords) this.drawRoute(this.map, coords.start, coords.end);
    }
  }
  
  

  ngOnChanges() {
    if (this.map && this.departure && this.arrival) {
      this.fetchCoordinates(this.departure, this.arrival).then(coords => {
        if (coords) this.drawRoute(this.map!, coords.start, coords.end);
      });
    }
  }

  private async drawIfCoordsReady() {
    if (!this.map || !this.departure || !this.arrival) return;

    const coords = await this.fetchCoordinates(this.departure, this.arrival);
    if (coords) this.drawRoute(this.map, coords.start, coords.end);
  }

  async fetchCoordinates(start: string, end: string): Promise<{ start: number[]; end: number[] } | null> {
    const key = environment.orsApiKey;
    const url = `https://api.openrouteservice.org/geocode/search`;

    try {
      const resStart: any = await this.http.get(url, {
        params: { api_key: key, text: start }
      }).toPromise();

      const resEnd: any = await this.http.get(url, {
        params: { api_key: key, text: end }
      }).toPromise();

      const startCoords = resStart.features[0].geometry.coordinates;
      const endCoords = resEnd.features[0].geometry.coordinates;

      return { start: startCoords, end: endCoords };
    } catch (err) {
      console.error('Erreur géocodage', err);
      return null;
    }
  }

  async drawRoute(map: L.Map, start: number[], end: number[]) {
    const key = environment.orsApiKey;
    const url = 'https://api.openrouteservice.org/v2/directions/driving-car/geojson';
  
    try {
      const response: any = await this.http.post(url, {
        coordinates: [start, end]
      }, {
        headers: { Authorization: key }
      }).toPromise();
  
      // Supprime le tracé précédent si présent
      if (this.routeLayer) {
        this.map?.removeLayer(this.routeLayer);
      }
  
      // Ajoute le nouveau tracé
      this.routeLayer = L.geoJSON(response, {
        style: { color: 'blue', weight: 4 }
      }).addTo(map);
  
      map.fitBounds(this.routeLayer.getBounds());
    } catch (err) {
      console.error('Erreur tracé itinéraire', err);
    }
  }
  
}
