export const USER_STATUS = {
  ACTIVE:    1,
  SUSPENDED: 2,
  INACTIVE:  3,
}


export function isUnavailableStatus(user) {
  const id = Number(user?.status_id)
  return id === USER_STATUS.SUSPENDED || id === USER_STATUS.INACTIVE
}

export function statusLabel(statusId) {
  switch (Number(statusId)) {
    case USER_STATUS.ACTIVE:    return 'Activa'
    case USER_STATUS.SUSPENDED: return 'Suspendida'
    case USER_STATUS.INACTIVE:  return 'Baja'
    default:                    return 'Desconocido'
  }
}