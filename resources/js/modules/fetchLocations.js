import locationsMockData from '../utils/locationsMockData.json';

export function fetchLocationsByBedrooms(bedrooms) {
  const allLocations = fetchLocations();

  const locations = allLocations.filter(location => Number(location.bedrooms) === Number(bedrooms));

  return locations;
}

export function fetchLocations() {
  return locationsMockData;
}
